// ignore_for_file: must_be_immutable, deprecated_member_use

import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:get/get.dart';
import 'package:zabi/shimmer/all_shimmer_loder.dart';
import 'package:zabi/view/base/custom_app_bar.dart';
import '../../../controller/hadith_controller.dart';
import '../../../helper/route_helper.dart';
import '../../../util/dimensions.dart';
import '../../../util/images.dart';
import '../../../util/styles.dart';

class HadisBookNameScreen extends StatelessWidget {
  final bool appBackButton;
  const HadisBookNameScreen({super.key, required this.appBackButton});

  @override
  Widget build(BuildContext context) {
    final ctrl = Get.find<HadithController>();
    return Scaffold(
      appBar: CustomAppBar(
        title: "hadith_books".tr,
        isBackButtonExist: appBackButton,
      ),
      body: Obx(() {
        if (ctrl.isLoadingBooks.value) {
          return const Center(child: HadisBookShimmer());
        }
        final books = ctrl.booksRx.value?.books;
        if (books == null || books.isEmpty) {
          return Center(
            child: Text("no_data_found".tr,
                style: robotoMedium.copyWith(fontSize: 16)),
          );
        }
        return ListView.builder(
          padding: const EdgeInsets.symmetric(
              horizontal: Dimensions.PADDING_SIZE_SMALL),
          itemCount: books.length,
          itemBuilder: (context, index) {
            final book = books[index];
            return GestureDetector(
              onTap: () {
                Get.toNamed(RouteHelper.hadithChapters,
                    arguments: [book.bookSlug, book.bookName]);
              },
              child: Card(
                clipBehavior: Clip.antiAlias,
                color: Theme.of(context).cardColor,
                child: ListTile(
                  contentPadding: const EdgeInsets.symmetric(
                      horizontal: Dimensions.PADDING_SIZE_EXTRA_SMALL + 2,
                      vertical: Dimensions.PADDING_SIZE_EXTRA_SMALL),
                  horizontalTitleGap: 0,
                  leading: SvgPicture.asset(
                    Images.Icon_Hadith,
                    color: Theme.of(context).primaryColor,
                    height: 50,
                  ),
                  title: Text(book.bookName ?? "",
                      style: robotoMedium.copyWith(
                          fontSize: Dimensions.FONT_SIZE_LARGE)),
                  subtitle: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const SizedBox(height: 5),
                      Text(book.writerName ?? "",
                          style: robotoMedium.copyWith(
                              fontSize: Dimensions.FONT_SIZE_DEFAULT)),
                      const SizedBox(height: 5),
                      Text("${"hadiths".tr}: ${book.hadithsCount ?? ""}",
                          style: robotoMedium.copyWith(
                              fontSize: Dimensions.FONT_SIZE_SMALL)),
                    ],
                  ),
                ),
              ),
            );
          },
        );
      }),
    );
  }
}
